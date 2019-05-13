import { orm } from '../../orm';

const fetchStudyClassesToDB = (dbState, action) => {
  const session = orm.session(dbState);

  const { StudyClass } = session;

  action.payload.forEach(studyClass => StudyClass.upsert(studyClass));

  return session.state;
};

const deleteStudyClassFromDB = (dbState, action) => {
  const session = orm.session(dbState);

  const { StudyClass } = session;

  const foundStudyClass = StudyClass.withId(action.payload.studyClassId);

  if (foundStudyClass) {
    foundStudyClass.delete();
  }

  return session.state;
};

export { fetchStudyClassesToDB, deleteStudyClassFromDB };
